document.addEventListener('DOMContentLoaded', () => {
    const routes = window.postRoutes || {};
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const modalElement = document.getElementById('postModal');
    const postModal = new bootstrap.Modal(modalElement);
    const deleteModalElement = document.getElementById('deletePostModal');
    const deletePostModal = new bootstrap.Modal(deleteModalElement);
    const form = document.getElementById('postForm');
    const table = document.getElementById('postsTable');
    const alertBox = document.getElementById('postAlert');
    const saveButton = document.getElementById('savePostBtn');
    const saveText = document.getElementById('savePostText');
    const saveSpinner = document.getElementById('savePostSpinner');
    const confirmDeleteButton = document.getElementById('confirmDeletePostBtn');
    const deleteText = document.getElementById('deletePostText');
    const deleteSpinner = document.getElementById('deletePostSpinner');
    const currentImageText = document.getElementById('currentImageText');
    let pendingDelete = null;

    const fields = {
        id: document.getElementById('postId'),
        content: document.getElementById('content'),
        categoryId: document.getElementById('category_id'),
        status: document.getElementById('status'),
        image: document.getElementById('image'),
        videoUrl: document.getElementById('video_url'),
    };

    const route = (template, id) => template.replace(':post', id);

    const escapeHtml = (value) => String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    const limit = (value, length = 80) => {
        const text = String(value || 'Media post');
        return text.length > length ? `${text.slice(0, length)}...` : text;
    };

    const imageUrl = (path) => {
        if (!path) {
            return null;
        }

        return path.startsWith('http') ? path : `${routes.storageBaseUrl}/${path}`;
    };

    const statusBadgeClass = (status) => {
        if (status === 'published') {
            return 'bg-success';
        }

        if (status === 'archived') {
            return 'bg-secondary';
        }

        return 'bg-warning text-dark';
    };

    const showAlert = (message, type = 'success') => {
        alertBox.textContent = message;
        alertBox.className = `alert alert-${type}`;

        window.setTimeout(() => {
            alertBox.classList.add('d-none');
        }, 4000);
    };

    const clearErrors = () => {
        form.querySelectorAll('.is-invalid').forEach((field) => field.classList.remove('is-invalid'));
        form.querySelectorAll('[data-error-for]').forEach((element) => {
            element.textContent = '';
        });
    };

    const showErrors = (errors) => {
        clearErrors();

        Object.entries(errors || {}).forEach(([name, messages]) => {
            const input = form.querySelector(`[name="${name}"]`);
            const error = form.querySelector(`[data-error-for="${name}"]`);

            input?.classList.add('is-invalid');

            if (error) {
                error.textContent = messages[0];
            }
        });
    };

    const setSaving = (isSaving) => {
        saveButton.disabled = isSaving;
        saveSpinner.classList.toggle('d-none', !isSaving);
        saveText.textContent = isSaving ? 'Saving...' : 'Save Post';
    };

    const setDeleting = (isDeleting) => {
        confirmDeleteButton.disabled = isDeleting;
        deleteSpinner.classList.toggle('d-none', !isDeleting);
        deleteText.textContent = isDeleting ? 'Deleting...' : 'Delete';
    };

    const resetForm = () => {
        form.reset();
        clearErrors();
        fields.id.value = '';
        fields.status.value = 'draft';
        currentImageText.textContent = 'JPG, PNG, or WebP up to 5 MB.';
        document.getElementById('postModalTitle').textContent = 'Create Post';
        document.getElementById('postModalSubtitle').textContent = 'Add content, media, and publishing status.';
    };

    const renderRow = (post) => {
        const preview = limit(post.content || post.video_url || 'Media post');
        const category = post.category?.name || 'Uncategorized';
        const author = post.user?.name || 'Unknown';
        const publishedAt = post.published_at
            ? new Date(post.published_at).toLocaleDateString(undefined, { month: 'short', day: '2-digit', year: 'numeric' })
            : '-';
        const createdAt = post.created_at
            ? new Date(post.created_at).toLocaleDateString(undefined, { month: 'short', day: '2-digit', year: 'numeric' })
            : 'Just now';
        const source = imageUrl(post.image_url);
        const thumb = source
            ? `<img src="${escapeHtml(source)}" alt="Post image">`
            : '<i class="bi bi-file-earmark-text text-muted"></i>';

        return `
            <tr id="postRow${post.id}">
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="post-thumb bg-light rounded overflow-hidden">${thumb}</div>
                        <div class="min-w-0">
                            <div class="fw-semibold text-truncate post-content-preview">${escapeHtml(preview)}</div>
                            <small class="text-muted">#${post.id} - ${escapeHtml(createdAt)}</small>
                        </div>
                    </div>
                </td>
                <td>${escapeHtml(category)}</td>
                <td>
                    <span class="badge ${statusBadgeClass(post.status)}">${escapeHtml(post.status.charAt(0).toUpperCase() + post.status.slice(1))}</span>
                </td>
                <td>${escapeHtml(author)}</td>
                <td>${escapeHtml(publishedAt)}</td>
                <td>
                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-sm btn-outline-primary editPostBtn" type="button" data-id="${post.id}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger deletePostBtn" type="button" data-id="${post.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    };

    document.getElementById('createPostBtn').addEventListener('click', () => {
        resetForm();
        postModal.show();
    });

    if (new URLSearchParams(window.location.search).get('create') === '1') {
        resetForm();
        postModal.show();
    }

    table.addEventListener('click', async (event) => {
        const editButton = event.target.closest('.editPostBtn');
        const deleteButton = event.target.closest('.deletePostBtn');

        if (editButton) {
            clearErrors();
            const id = editButton.dataset.id;
            editButton.disabled = true;

            try {
                const response = await fetch(route(routes.show, id), {
                    headers: { Accept: 'application/json' },
                });
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Unable to load post.');
                }

                fields.id.value = data.post.id;
                fields.content.value = data.post.content || '';
                fields.categoryId.value = data.post.category_id || '';
                fields.status.value = data.post.status || 'draft';
                fields.videoUrl.value = data.post.video_url || '';
                fields.image.value = '';
                currentImageText.textContent = data.post.image_url ? 'Current image will be kept unless you choose a new one.' : 'JPG, PNG, or WebP up to 5 MB.';

                document.getElementById('postModalTitle').textContent = 'Edit Post';
                document.getElementById('postModalSubtitle').textContent = `Post #${data.post.id}`;
                postModal.show();
            } catch (error) {
                showAlert(error.message, 'danger');
            } finally {
                editButton.disabled = false;
            }
        }

        if (deleteButton) {
            const id = deleteButton.dataset.id;

            pendingDelete = { id, button: deleteButton };
            deletePostModal.show();
        }
    });

    confirmDeleteButton.addEventListener('click', async () => {
        if (!pendingDelete) {
            return;
        }

        const { id, button } = pendingDelete;

        button.disabled = true;
        setDeleting(true);

        try {
            const response = await fetch(route(routes.destroy, id), {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Unable to delete post.');
            }

            document.getElementById(`postRow${id}`)?.remove();

            if (!table.querySelector('tr')) {
                table.innerHTML = '<tr id="emptyPostsRow"><td colspan="6" class="text-center py-5 text-muted">No posts found.</td></tr>';
            }

            deletePostModal.hide();
            showAlert(data.message);
            pendingDelete = null;
        } catch (error) {
            showAlert(error.message, 'danger');
            button.disabled = false;
        } finally {
            setDeleting(false);
        }
    });

    deleteModalElement.addEventListener('hidden.bs.modal', () => {
        if (pendingDelete?.button) {
            pendingDelete.button.disabled = false;
        }

        pendingDelete = null;
        setDeleting(false);
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearErrors();
        setSaving(true);

        const id = fields.id.value;
        const formData = new FormData(form);
        formData.delete('post_id');

        let url = routes.store;

        if (id) {
            url = route(routes.update, id);
            formData.append('_method', 'PUT');
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            });
            const data = await response.json();

            if (response.status === 422) {
                showErrors(data.errors);
                return;
            }

            if (!response.ok) {
                throw new Error(data.message || 'Unable to save post.');
            }

            const html = renderRow(data.post);
            const currentRow = document.getElementById(`postRow${data.post.id}`);

            if (currentRow) {
                currentRow.outerHTML = html;
            } else {
                document.getElementById('emptyPostsRow')?.remove();
                table.insertAdjacentHTML('afterbegin', html);
            }

            postModal.hide();
            showAlert(data.message);
        } catch (error) {
            showAlert(error.message, 'danger');
        } finally {
            setSaving(false);
        }
    });
});
