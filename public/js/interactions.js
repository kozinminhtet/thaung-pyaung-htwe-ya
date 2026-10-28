document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const requestJson = async (url, options = {}) => {
        const response = await fetch(url, {
            ...options,
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                ...options.headers,
            },
        });

        let data = {};
        try {
            data = await response.json();
        } catch (_) {
            // The status code below still gives the caller a useful failure.
        }

        if (!response.ok) {
            const validationMessage = data.errors
                ? Object.values(data.errors).flat().join(' ')
                : null;
            throw new Error(validationMessage || data.message || 'The request could not be completed.');
        }

        return data;
    };

    const setFeedback = (card, message, isError = false, inComments = false) => {
        const target = inComments
            ? card.querySelector('[data-interaction-feedback]')
            : card.querySelector('[data-global-feedback]');
        if (!target) return;
        target.textContent = message;
        target.classList.toggle('text-danger', isError);
        target.classList.toggle('text-success', !isError && Boolean(message));
    };

    const updateCount = (card, type, count) => {
        const target = card.querySelector(`[data-count="${type}"]`);
        if (target) target.textContent = Number(count).toLocaleString();
    };

    const updateToggleButton = (button, active, data) => {
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
        button.classList.toggle('is-active', active);
        const previousUrl = button.dataset.url;
        button.dataset.url = button.dataset.nextUrl;
        button.dataset.nextUrl = previousUrl;

        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.toggle('far', !active);
            icon.classList.toggle('fas', active);
        }
        updateCount(button.closest('[data-post-interactions]'), data.kind, data.count);
    };

    const appendComment = (list, comment) => {
        const item = document.createElement('article');
        item.className = 'post-comment-item border-bottom py-2';

        const header = document.createElement('div');
        header.className = 'd-flex justify-content-between gap-2';
        const author = document.createElement('strong');
        author.textContent = comment.author;
        const time = document.createElement('small');
        time.className = 'text-muted flex-shrink-0';
        time.textContent = comment.created_at;
        header.append(author, time);

        const content = document.createElement('p');
        content.className = 'mb-0 mt-1';
        content.textContent = comment.content;
        item.append(header, content);
        list.append(item);
        return item;
    };

    const loadComments = async (button, card) => {
        const panel = card.querySelector('[data-comments-panel]');
        const list = card.querySelector('[data-comments-list]');
        const opening = panel.hidden;
        panel.hidden = !opening;
        button.setAttribute('aria-expanded', opening ? 'true' : 'false');
        if (!opening || panel.dataset.loaded === 'true') return;

        list.textContent = 'Loading comments…';
        try {
            const data = await requestJson(button.dataset.url, { method: 'GET' });
            list.replaceChildren();
            if (data.comments.length === 0) {
                list.textContent = 'No comments yet.';
            } else {
                data.comments.forEach((comment) => appendComment(list, comment));
            }
            const moreButton = card.querySelector('[data-comments-more]');
            moreButton.hidden = !data.has_more;
            moreButton.dataset.beforeId = data.next_before_id || '';
            panel.dataset.loaded = 'true';
        } catch (error) {
            list.textContent = error.message;
        }
    };

    const copyToClipboard = async (text) => {
        if (navigator.clipboard?.writeText) {
            try {
                await navigator.clipboard.writeText(text);
                return true;
            } catch (_) {
                // Try the legacy clipboard command for browsers that deny the API.
            }
        }

        const input = document.createElement('textarea');
        input.value = text;
        input.setAttribute('readonly', '');
        input.style.position = 'fixed';
        input.style.opacity = '0';
        document.body.append(input);
        input.select();
        let copied = false;
        try {
            copied = document.execCommand('copy');
        } catch (_) {
            copied = false;
        }
        input.remove();
        return copied;
    };

    const recordShareAction = async (button, card, platform) => {
        if (!button.dataset.trackUrl) return;

        try {
            const data = await requestJson(button.dataset.trackUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ platform }),
            });
            updateCount(card, 'share', data.count);
        } catch (error) {
            setFeedback(card, `Action completed, but could not update its count: ${error.message}`, true);
        }
    };

    const showLoginModal = () => {
        const modalElement = document.getElementById('interactionLoginModal');
        if (!modalElement || !window.bootstrap?.Modal) return;
        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
    };

    document.addEventListener('click', async (event) => {
        const loginTrigger = event.target.closest('[data-requires-login]:not(form)');
        if (loginTrigger) {
            if (loginTrigger.form) event.preventDefault();
            showLoginModal();
            return;
        }

        const shareOption = event.target.closest('[data-share-platform]');
        if (shareOption) {
            const card = shareOption.closest('[data-post-interactions]');
            const platform = shareOption.dataset.sharePlatform;
            const postUrl = new URL(shareOption.dataset.url, window.location.origin).href;
            const title = card.closest('.card')?.querySelector('h6')?.textContent?.trim() || document.title;
            shareOption.disabled = true;

            try {
                if (platform === 'copy') {
                    const copied = await copyToClipboard(postUrl);
                    if (!copied) {
                        setFeedback(card, 'Could not copy the post link. Please copy it from the address bar.', true);
                        return;
                    }
                    setFeedback(card, 'Post link copied.');
                    await recordShareAction(shareOption, card, platform);
                } else {
                    const destination = new URL(platform === 'facebook'
                        ? 'https://www.facebook.com/sharer/sharer.php'
                        : 'https://t.me/share/url');
                    if (platform === 'facebook') {
                        destination.searchParams.set('u', postUrl);
                    } else if (platform === 'telegram') {
                        destination.searchParams.set('url', postUrl);
                        destination.searchParams.set('text', title);
                    } else {
                        return;
                    }

                    const popup = window.open(destination.toString(), '_blank');
                    if (!popup) {
                        setFeedback(card, 'The sharing window was blocked. Allow pop-ups and try again.', true);
                        return;
                    }
                    popup.opener = null;
                    setFeedback(card, `${platform === 'facebook' ? 'Facebook' : 'Telegram'} opened. Share completion is not confirmed.`);
                    await recordShareAction(shareOption, card, platform);
                }
            } catch (error) {
                setFeedback(card, error.message || 'Could not start sharing this post.', true);
            } finally {
                shareOption.disabled = false;
            }
            return;
        }

        const moreButton = event.target.closest('[data-comments-more]');
        if (moreButton) {
            if (moreButton.disabled || !moreButton.dataset.beforeId) return;
            const card = moreButton.closest('[data-post-interactions]');
            const panel = card.querySelector('[data-comments-panel]');
            const list = card.querySelector('[data-comments-list]');
            const url = new URL(panel.dataset.commentsUrl, window.location.origin);
            url.searchParams.set('before_id', moreButton.dataset.beforeId);
            moreButton.disabled = true;
            try {
                const data = await requestJson(url.toString(), { method: 'GET' });
                const firstComment = list.querySelector('.post-comment-item');
                const fragment = document.createDocumentFragment();
                data.comments.forEach((comment) => {
                    const item = appendComment(document.createElement('div'), comment);
                    fragment.append(item);
                });
                if (firstComment) list.insertBefore(fragment, firstComment);
                else list.replaceChildren(fragment);
                moreButton.hidden = !data.has_more;
                moreButton.dataset.beforeId = data.next_before_id || '';
            } catch (error) {
                setFeedback(card, error.message, true, true);
            } finally {
                moreButton.disabled = false;
            }
            return;
        }

        const button = event.target.closest('[data-action]');
        if (!button) return;

        const card = button.closest('[data-post-interactions]');
        if (!card) return;

        if (button.dataset.action === 'comments') {
            await loadComments(button, card);
            return;
        }

        if (button.dataset.action === 'share') return;

        if (button.disabled) return;
        button.disabled = true;
        const action = button.dataset.action;
        setFeedback(card, action === 'share'
            ? 'Opening share…'
            : `Updating ${action}…`);

        try {
            if (button.dataset.action === 'share') {
                await sharePost(button, card);
                return;
            }

            const wasActive = button.getAttribute('aria-pressed') === 'true';
            const method = wasActive ? 'DELETE' : 'PUT';
            const data = await requestJson(button.dataset.url, { method });
            const active = action === 'like' ? data.liked : data.saved;
            updateToggleButton(button, active, { ...data, kind: action });
            if (action === 'save' && !active) {
                const savedCard = button.closest('[data-saved-post-card]');
                if (savedCard) {
                    const list = savedCard.closest('[data-saved-posts-list]');
                    savedCard.remove();
                    if (list && !list.querySelector('[data-saved-post-card]')) {
                        const currentPage = Number(list.dataset.currentPage);
                        const lastPage = Number(list.dataset.lastPage);
                        if (currentPage < lastPage || currentPage > 1) {
                            const destination = new URL(window.location.href);
                            destination.searchParams.set('page', currentPage < lastPage ? currentPage + 1 : currentPage - 1);
                            window.location.assign(destination.toString());
                            return;
                        }
                        const emptyState = document.createElement('div');
                        emptyState.className = 'card mb-2';
                        emptyState.dataset.savedEmpty = '';
                        emptyState.innerHTML = `<div class="card-body text-center text-muted py-5"><i class="bi bi-bookmark display-6 d-block mb-2" aria-hidden="true"></i>You have no saved posts yet.<div class="mt-3"><a href="${list.dataset.exploreUrl}" class="btn btn-primary btn-sm">Explore posts</a></div></div>`;
                        list.append(emptyState);
                        document.querySelector('[data-saved-pagination]')?.remove();
                    }
                    return;
                }
            }
            setFeedback(card, 'Updated.');
        } catch (error) {
            setFeedback(card, error.message, true);
        } finally {
            button.disabled = false;
        }
    });

    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('[data-comment-form]');
        if (!form) return;
        event.preventDefault();
        if (form.hasAttribute('data-requires-login')) {
            showLoginModal();
            return;
        }
        if (form.dataset.busy === 'true') return;

        const card = form.closest('[data-post-interactions]');
        const input = form.elements.content;
        const content = input.value.trim();
        if (!content) {
            setFeedback(card, 'Please enter a comment.', true, true);
            return;
        }

        form.dataset.busy = 'true';
        const submit = form.querySelector('button[type="submit"]');
        submit.disabled = true;
        setFeedback(card, 'Sending comment…', false, true);

        try {
            const data = await requestJson(form.action, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ content }),
            });

            const list = card.querySelector('[data-comments-list]');
            if (list.textContent === 'No comments yet.' || list.textContent === 'Loading comments…') {
                list.replaceChildren();
            }
            appendComment(list, data.comment);
            card.querySelector('[data-comments-panel]').dataset.loaded = 'true';
            updateCount(card, 'comment', data.count);
            input.value = '';
            setFeedback(card, 'Comment posted.', false, true);
        } catch (error) {
            setFeedback(card, error.message, true, true);
        } finally {
            form.dataset.busy = 'false';
            submit.disabled = false;
        }
    });
});
