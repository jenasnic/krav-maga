const bindMenuBurger = (icon) => {
    icon.addEventListener('click', () => {
        icon.classList.toggle('close');
        document.querySelector('#menu-wrapper .menu').classList.toggle('active');
    });
};

[...document.querySelectorAll('#menu-wrapper .menu-burger')].forEach(bindMenuBurger);
