document.addEventListener("DOMContentLoaded", function () {
    // --- ၁။ Element များကို ရှာဖွေခြင်း ---
    const menuBtn = document.getElementById("mobileMenuBtn");
    const mobileMenu = document.getElementById("mobileMenu");
    const bottomNav = document.querySelector(".bottom-nav");
    const backBtn = document.querySelector(".floating-back-btn");

    // --- ၂။ Mobile Menu Logic ---
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            // ပိုမိုချောမွေ့စေရန် class toggle သုံးနိုင်သလို display ကိုလည်း စစ်နိုင်သည်
            const isOpen = mobileMenu.style.display === "block";
            mobileMenu.style.display = isOpen ? "none" : "block";
        });

        document.addEventListener("click", function (e) {
            if (!mobileMenu.contains(e.target) && e.target !== menuBtn) {
                mobileMenu.style.display = "none";
            }
        });
    }

    // --- ၃။ Smooth Scroll Logic (Bottom Nav & Floating Button) ---
    let lastScroll = window.pageYOffset || document.documentElement.scrollTop;

    window.addEventListener("scroll", function () {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        
        // Scroll ပမာဏ အရမ်းနည်းနေလျှင် ဘာမှမလုပ်ဘဲ ထားမည် (တုန်တာ သက်သာစေရန်)
        if (Math.abs(currentScroll - lastScroll) < 5) return;

        if (currentScroll > lastScroll && currentScroll > 80) {
            // Scroll Down - အကုန်ဖျောက်မည်
            if (bottomNav) bottomNav.style.transform = "translateY(100%)";
            if (backBtn) {
                backBtn.style.transform = "translateY(150%)"; // အောက်သို့ ငုပ်သွားမည်
                backBtn.style.opacity = "0";
            }
        } else {
            // Scroll Up - အကုန်ပြန်ပြမည်
            if (bottomNav) bottomNav.style.transform = "translateY(0)";
            if (backBtn) {
                backBtn.style.transform = "translateY(0)";
                backBtn.style.opacity = "1";
            }
        }

        lastScroll = currentScroll <= 0 ? 0 : currentScroll;
    }, { passive: true }); // Performance ပိုကောင်းစေရန် passive option သုံးသည်
});