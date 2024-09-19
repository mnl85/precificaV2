<div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
        <i class="material-icons py-2">settings</i>
    </a>
    <div class="card shadow-lg">
        <div class="card-header pb-0 pt-3">
            <div class="float-start">
                <h5 class="mt-3 mb-0">Configuração do Visual</h5>
            <br>
            </div>
            <div class="float-end mt-4">
                <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                    <i class="material-icons">clear</i>
                </button>
            </div>
        </div>
        <hr class="horizontal dark my-1">
        <div class="card-body pt-sm-3 pt-0">
        <br>
            <!-- Sidebar Backgrounds -->
            <div>
                <h6 class="mb-0">Barra Lateral - Cor da Seleção</h6>
            </div>
            <a href="javascript:void(0)" class="switch-trigger background-color">
                <div class="badge-colors my-2 text-start">
                    <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
                    <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
                </div>
            </a>
            <br>
            <!-- Sidenav Type -->
            <div class="mt-3">
                <h6 class="mb-0">Barra Lateral - Cor do Módulo</h6>
    
            </div>
            <div class="d-flex">
                <button class="btn bg-gradient-dark px-3 mb-2 active" data-class="bg-gradient-dark" onclick="sidebarType(this)">Escuro</button>
                <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Cristal</button>
                <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">Branco</button>
            </div>
            <br>
            <!-- Navbar Fixed -->
            <div class="mt-3 d-flex">
                <h6 class="mb-0">Barra de Navegação Fixa</h6>
                <div class="form-check form-switch ps-0 ms-auto my-auto">
                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
                </div>
            </div>
            <hr class="horizontal dark my-3">
            <div class="mt-2 d-flex">
                <h6 class="mb-0">Mini Barra Lateral</h6>
                <div class="form-check form-switch ps-0 ms-auto my-auto">
                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarMinimize" onclick="navbarMinimize(this)">
                </div>
            </div>
            <hr class="horizontal dark my-3">
            <div class="mt-2 d-flex">
                <h6 class="mb-0">Modo Claro / Escuro</h6>
                <div class="form-check form-switch ps-0 ms-auto my-auto">
                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
                </div>
            </div>
            <hr class="horizontal dark my-sm-4"> 
        </div>
    </div>
</div>
<script>

function saveToSession(key, value) {
      fetch('/save-ui-settings', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ key: key, value: value })
      })
      .then(response => response.json())
      .then(data => {
  
      })
      .catch((error) => {
          console.error('Error:', error);
      });
    }

    function sidebarColor(a) {
      var parent = a.parentElement.children;
      var color = a.getAttribute("data-color");
      saveToSession('sidebar_color', color);

      for (var i = 0; i < parent.length; i++) {
        parent[i].classList.remove('active');
      }

      if (!a.classList.contains('active')) {
        a.classList.add('active');
      } else {
        a.classList.remove('active');
      }

      var sidebar = document.querySelector('.sidenav');
      sidebar.setAttribute("data-color", color);

      if (document.querySelector('#sidenavCard')) {
        var sidenavCard = document.querySelector('#sidenavCard');
        let sidenavCardClasses = ['card', 'card-background', 'shadow-none', 'card-background-mask-' + color];
        sidenavCard.className = '';
        sidenavCard.classList.add(...sidenavCardClasses);

        var sidenavCardIcon = document.querySelector('#sidenavCardIcon');
        let sidenavCardIconClasses = ['ni', 'ni-diamond', 'text-gradient', 'text-lg', 'top-0', 'text-' + color];
        sidenavCardIcon.className = '';
        sidenavCardIcon.classList.add(...sidenavCardIconClasses);
      }
    }

    function sidebarType(a) {
      var parent = a.parentElement.children;
      var color = a.getAttribute("data-class");
      var body = document.querySelector("body");
      var bodyWhite = document.querySelector("body:not(.dark-version)");
      var bodyDark = body.classList.contains('dark-version');

      var type = a.getAttribute('data-class');
      saveToSession('sidebar_type', type);

      var colors = [];

      for (var i = 0; i < parent.length; i++) {
        parent[i].classList.remove('active');
        colors.push(parent[i].getAttribute('data-class'));
      }

      if (!a.classList.contains('active')) {
        a.classList.add('active');
      } else {
        a.classList.remove('active');
      }

      var sidebar = document.querySelector('.sidenav');

      for (var i = 0; i < colors.length; i++) {
        sidebar.classList.remove(colors[i]);
      }

      sidebar.classList.add(color);

      // Remove text-white/text-dark classes
      if (color == 'bg-transparent' || color == 'bg-white') {
        var textWhites = document.querySelectorAll('.sidenav .text-white');
        for (let i = 0; i < textWhites.length; i++) {
          textWhites[i].classList.remove('text-white');
          textWhites[i].classList.add('text-dark');
        }
      } else {
        var textDarks = document.querySelectorAll('.sidenav .text-dark');
        for (let i = 0; i < textDarks.length; i++) {
          textDarks[i].classList.add('text-white');
          textDarks[i].classList.remove('text-dark');
        }
      }

      if (color == 'bg-transparent' && bodyDark) {
        var textDarks = document.querySelectorAll('.navbar-brand .text-dark');
        for (let i = 0; i < textDarks.length; i++) {
          textDarks[i].classList.add('text-white');
          textDarks[i].classList.remove('text-dark');
        }
      }

      // Remove logo-white/logo-dark

      if ((color == 'bg-transparent' || color == 'bg-white') && bodyWhite) {
        var navbarBrand = document.querySelector('.navbar-brand-img');
        var navbarBrandImg = navbarBrand.src;

        if (navbarBrandImg.includes('logo-ct.png')) {
          var navbarBrandImgNew = navbarBrandImg.replace("logo-ct", "logo-ct-dark");
          navbarBrand.src = navbarBrandImgNew;
        }
      } else {
        var navbarBrand = document.querySelector('.navbar-brand-img');
        var navbarBrandImg = navbarBrand.src;
        if (navbarBrandImg.includes('logo-ct-dark.png')) {
          var navbarBrandImgNew = navbarBrandImg.replace("logo-ct-dark", "logo-ct");
          navbarBrand.src = navbarBrandImgNew;
        }
      }

      if (color == 'bg-white' && bodyDark) {
        var navbarBrand = document.querySelector('.navbar-brand-img');
        var navbarBrandImg = navbarBrand.src;

        if (navbarBrandImg.includes('logo-ct.png')) {
          var navbarBrandImgNew = navbarBrandImg.replace("logo-ct", "logo-ct-dark");
          navbarBrand.src = navbarBrandImgNew;
        }
      }
    }

    function navbarFixed(el) {
      let classes = ['position-sticky', 'blur', 'shadow-blur', 'mt-4', 'left-auto', 'top-1', 'z-index-sticky'];
      const navbar = document.getElementById('navbarBlur');

      var isFixed = el.checked;

      saveToSession('navbar_fixed', isFixed);

      if (!el.getAttribute("checked")) {
        navbar.classList.add(...classes);
        navbar.setAttribute('data-scroll', 'true');
        navbarBlurOnScroll('navbarBlur');
        el.setAttribute("checked", "true");
      } else {
        navbar.classList.remove(...classes);
        navbar.setAttribute('data-scroll', 'false');
        navbarBlurOnScroll('navbarBlur');
        el.removeAttribute("checked");
      }
    };

    function navbarMinimize(el) {
      var sidenavShow = document.getElementsByClassName('g-sidenav-show')[0];

      var isMinimized = el.checked;

      saveToSession('navbar_minimize', isMinimized);

      if (!el.getAttribute("checked")) {
        sidenavShow.classList.remove('g-sidenav-pinned');
        sidenavShow.classList.add('g-sidenav-hidden');
        el.setAttribute("checked", "true");
      } else {
        sidenavShow.classList.remove('g-sidenav-hidden');
        sidenavShow.classList.add('g-sidenav-pinned');
        el.removeAttribute("checked");
      }
    }

    function darkMode(el) {
      const body = document.getElementsByTagName('body')[0];
      const hr = document.querySelectorAll('div:not(.sidenav) > hr');
      const hr_card = document.querySelectorAll('div:not(.bg-gradient-dark) hr');
      const text_btn = document.querySelectorAll('button:not(.btn) > .text-dark');
      const text_span = document.querySelectorAll('span.text-dark, .breadcrumb .text-dark');
      const text_span_white = document.querySelectorAll('span.text-white, .breadcrumb .text-white');
      const text_strong = document.querySelectorAll('strong.text-dark');
      const text_strong_white = document.querySelectorAll('strong.text-white');
      const text_nav_link = document.querySelectorAll('a.nav-link.text-dark');
      const text_nav_link_white = document.querySelectorAll('a.nav-link.text-white');
      const secondary = document.querySelectorAll('.text-secondary');
      const bg_gray_100 = document.querySelectorAll('.bg-gray-100');
      const bg_gray_600 = document.querySelectorAll('.bg-gray-600');
      const btn_text_dark = document.querySelectorAll('.btn.btn-link.text-dark, .material-icons.text-dark');
      const btn_text_white = document.querySelectorAll('.btn.btn-link.text-white, .material-icons.text-white');
      const card_border = document.querySelectorAll('.card.border');
      const card_border_dark = document.querySelectorAll('.card.border.border-dark');

      var isDarkMode = el.checked;
      saveToSession('dark_mode', isDarkMode);

      const svg = document.querySelectorAll('g');

      if (!el.getAttribute("checked")) {
        body.classList.add('dark-version');
        for (var i = 0; i < hr.length; i++) {
          if (hr[i].classList.contains('dark')) {
            hr[i].classList.remove('dark');
            hr[i].classList.add('light');
          }
        }

        for (var i = 0; i < hr_card.length; i++) {
          if (hr_card[i].classList.contains('dark')) {
            hr_card[i].classList.remove('dark');
            hr_card[i].classList.add('light');
          }
        }
        for (var i = 0; i < text_btn.length; i++) {
          if (text_btn[i].classList.contains('text-dark')) {
            text_btn[i].classList.remove('text-dark');
            text_btn[i].classList.add('text-white');
          }
        }
        for (var i = 0; i < text_span.length; i++) {
          if (text_span[i].classList.contains('text-dark')) {
            text_span[i].classList.remove('text-dark');
            text_span[i].classList.add('text-white');
          }
        }
        for (var i = 0; i < text_strong.length; i++) {
          if (text_strong[i].classList.contains('text-dark')) {
            text_strong[i].classList.remove('text-dark');
            text_strong[i].classList.add('text-white');
          }
        }
        for (var i = 0; i < text_nav_link.length; i++) {
          if (text_nav_link[i].classList.contains('text-dark')) {
            text_nav_link[i].classList.remove('text-dark');
            text_nav_link[i].classList.add('text-white');
          }
        }
        for (var i = 0; i < secondary.length; i++) {
          if (secondary[i].classList.contains('text-secondary')) {
            secondary[i].classList.remove('text-secondary');
            secondary[i].classList.add('text-white');
            secondary[i].classList.add('opacity-8');
          }
        }
        for (var i = 0; i < bg_gray_100.length; i++) {
          if (bg_gray_100[i].classList.contains('bg-gray-100')) {
            bg_gray_100[i].classList.remove('bg-gray-100');
            bg_gray_100[i].classList.add('bg-gray-600');
          }
        }
        for (var i = 0; i < btn_text_dark.length; i++) {
          btn_text_dark[i].classList.remove('text-dark');
          btn_text_dark[i].classList.add('text-white');
        }
        for (var i = 0; i < svg.length; i++) {
          if (svg[i].hasAttribute('fill')) {
            svg[i].setAttribute('fill', '#fff');
          }
        }
        for (var i = 0; i < card_border.length; i++) {
          card_border[i].classList.add('border-dark');
        }
        el.setAttribute("checked", "true");
      } else {
        body.classList.remove('dark-version');
        for (var i = 0; i < hr.length; i++) {
          if (hr[i].classList.contains('light')) {
            hr[i].classList.add('dark');
            hr[i].classList.remove('light');
          }
        }
        for (var i = 0; i < hr_card.length; i++) {
          if (hr_card[i].classList.contains('light')) {
            hr_card[i].classList.add('dark');
            hr_card[i].classList.remove('light');
          }
        }
        for (var i = 0; i < text_btn.length; i++) {
          if (text_btn[i].classList.contains('text-white')) {
            text_btn[i].classList.remove('text-white');
            text_btn[i].classList.add('text-dark');
          }
        }
        for (var i = 0; i < text_span_white.length; i++) {
          if (text_span_white[i].classList.contains('text-white') && !text_span_white[i].closest('.sidenav') && !text_span_white[i].closest('.card.bg-gradient-dark')) {
            text_span_white[i].classList.remove('text-white');
            text_span_white[i].classList.add('text-dark');
          }
        }
        for (var i = 0; i < text_strong_white.length; i++) {
          if (text_strong_white[i].classList.contains('text-white')) {
            text_strong_white[i].classList.remove('text-white');
            text_strong_white[i].classList.add('text-dark');
          }
        }
        for (var i = 0; i < text_nav_link_white.length; i++) {
          if (text_nav_link_white[i].classList.contains('text-white') && !text_nav_link_white[i].closest('.sidenav')) {
            text_nav_link_white[i].classList.remove('text-white');
            text_nav_link_white[i].classList.add('text-dark');
          }
        }
        for (var i = 0; i < secondary.length; i++) {
          if (secondary[i].classList.contains('text-white')) {
            secondary[i].classList.remove('text-white');
            secondary[i].classList.remove('opacity-8');
            secondary[i].classList.add('text-dark');
          }
        }
        for (var i = 0; i < bg_gray_600.length; i++) {
          if (bg_gray_600[i].classList.contains('bg-gray-600')) {
            bg_gray_600[i].classList.remove('bg-gray-600');
            bg_gray_600[i].classList.add('bg-gray-100');
          }
        }
        for (var i = 0; i < svg.length; i++) {
          if (svg[i].hasAttribute('fill')) {
            svg[i].setAttribute('fill', '#252f40');
          }
        }
        for (var i = 0; i < btn_text_white.length; i++) {
          if (!btn_text_white[i].closest('.card.bg-gradient-dark')) {
            btn_text_white[i].classList.remove('text-white');
            btn_text_white[i].classList.add('text-dark');
          }
        }
        for (var i = 0; i < card_border_dark.length; i++) {
          card_border_dark[i].classList.remove('border-dark');
        }
        el.removeAttribute("checked");
      }
    }
</script>

