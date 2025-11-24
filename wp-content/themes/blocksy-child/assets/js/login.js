/* JS mínimo para alternar pestañas de Iniciar sesión / Crear cuenta */
(function(){
    'use strict';
    function $(s,root=document){return root.querySelector(s);} 
    function $$(s,root=document){return Array.from(root.querySelectorAll(s));}

    document.addEventListener('DOMContentLoaded', function(){
        var tabs = $$('.cc-login-tab');
        var panels = { 'login': $('.cc-panel-login'), 'register': $('.cc-panel-register') };

        function setActive(name){
            tabs.forEach(function(t){ t.classList.toggle('active', t.dataset.tab===name); });
            if (panels.login) panels.login.classList.toggle('cc-hidden', name!=='login');
            if (panels.register) panels.register.classList.toggle('cc-hidden', name!=='register');
        }

        tabs.forEach(function(tab){
            tab.addEventListener('click', function(){ setActive(this.dataset.tab); });
        });

        // activar por defecto 'login'
        setActive('login');
    });
})();
