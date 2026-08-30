import 'bootstrap';
import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function () {

    // ===================== DELETE CONFIRM (SweetAlert2) =====================
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const form = this.closest('.delete-form');

            if (!form) return;   // ✅ OK — ဒါက Function ထဲမှာမို့ Return သုံးလို့ရတယ်

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to recover this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // ===================== THEME TOGGLE =====================
    const toggleBtn = document.getElementById('themeToggleBtn') || document.getElementById('themeToggle');
    const icon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');
    const html = document.documentElement;

    if (toggleBtn) {   // ✅ "return" အစား "if" ဖြင့် Wrap လိုက်ပါတယ်
        function updateIcon() {
            const current = html.getAttribute('data-bs-theme');

            if (icon) {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }

            if (themeText) {
                themeText.textContent = current === 'dark' ? 'Light' : 'Dark';
            }
        }

        updateIcon();

        toggleBtn.addEventListener('click', function () {
            const current = html.getAttribute('data-bs-theme');
            const next = current === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-bs-theme', next);
            localStorage.setItem('theme', next);
            updateIcon();
        });
    }

    // ===================== ALERT AUTO-HIDE =====================
    setTimeout(function () {
        const alertEl = document.getElementById('successAlert');
        if (alertEl) {
            alertEl.classList.remove('show');
            setTimeout(() => alertEl.remove(), 300);
        }
    }, 3000);

    // ===================== NAV INDICATOR =====================
    const navList = document.getElementById('navList');
    const indicator = document.getElementById('navIndicator');

    if (navList && indicator) {
        function moveIndicator(el) {
            if (!el) {
                indicator.style.opacity = '0';
                return;
            }

            const left = el.offsetLeft;
            const width = el.offsetWidth;

            indicator.style.width = width + 'px';
            indicator.style.left = left + 'px';
            indicator.style.opacity = '1';
        }

        const activeLink = navList.querySelector('.nav-link.is-active');
        moveIndicator(activeLink);

        navList.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('mouseenter', () => moveIndicator(link));
        });

        navList.addEventListener('mouseleave', () => {
            moveIndicator(navList.querySelector('.nav-link.is-active'));
        });

        window.addEventListener('resize', () => {
            moveIndicator(navList.querySelector('.nav-link.is-active'));
        });
    }

});