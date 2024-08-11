/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

(() => {
    'use strict'
  
    const getStoredTheme = () => localStorage.getItem('theme')
    const setStoredTheme = theme => localStorage.setItem('theme', theme)
  
    const getPreferredTheme = () => {
      const storedTheme = getStoredTheme()
      if (storedTheme) {
        return storedTheme
      }
  
      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
    }
  
    const setTheme = theme => {
      if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.setAttribute('data-bs-theme', 'dark')
      } else {
        document.documentElement.setAttribute('data-bs-theme', theme)
      }
    }
  
    setTheme(getPreferredTheme())
  
    const showActiveTheme = (theme, focus = false) => {
      const themeSwitcher = document.querySelector('#bd-theme')
  
      if (!themeSwitcher) {
        return
      }
  
      const themeSwitcherText = document.querySelector('#bd-theme-text')
      const activeThemeIcon = document.querySelector('#i-icon-them')
      const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
      //const svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('href')
  
      document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
        element.classList.remove('active')
        element.setAttribute('aria-pressed', 'false')
      })
  
      btnToActive.classList.add('active')
      btnToActive.setAttribute('aria-pressed', 'true')
      activeThemeIcon.setAttribute('class', (theme == 'dark' ? 'bi bi-moon-stars-fill' : 'bi bi-sun-fill'))
      //const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`
      //themeSwitcher.setAttribute('aria-label', themeSwitcherLabel)
  
      if (focus) {
        themeSwitcher.focus()
      }
    }
  
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      const storedTheme = getStoredTheme()
      if (storedTheme !== 'light' && storedTheme !== 'dark') {
        setTheme(getPreferredTheme())
      }
    })
  
    window.addEventListener('DOMContentLoaded', () => {
      showActiveTheme(getPreferredTheme())
  
      document.querySelectorAll('[data-bs-theme-value]')
        .forEach(toggle => {
          toggle.addEventListener('click', () => {
            const theme = toggle.getAttribute('data-bs-theme-value')
            setStoredTheme(theme)
            setTheme(theme)
            showActiveTheme(theme, true)
          })
        })
    })
  })()
  

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    $(document).on("keyup", ".password-match", function () {
        var password = document.getElementById($(this).data('passwd'));
        var confirm_password = document.getElementById($(this).attr('id'));
        function validatePassword() {
            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Las contraseñas no coinciden.");
            } else {
                confirm_password.setCustomValidity('');
            }
        }
        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    });


    $(document).on("click", ".btn-show-passwd", function () {
        if ($('#' + $(this).data('passwd')).attr('type') == 'text') {
            $('#' + $(this).data('passwd')).attr('type', 'password');
            $(this).addClass('bi-eye-slash').removeClass('bi-eye');
        } else {
            $('#' + $(this).data('passwd')).attr('type', 'text');
            $(this).addClass('bi-eye').removeClass('bi-eye-slash');
        }
    });

    $('.select-dropdown').find('a').click(function (e) {
        var btn = $(this).data("btn");
        $("#" + btn).html('<i class="' + $(this).data("icon") + '"></i> <span class="d-none d-md-inline-block">' + $(this).data("desc") + '</span>');
        e.preventDefault();
    });

    $('.no-cerrar').on('click', function (event) {
        event.stopPropagation();
    });
});
