import '../styles/app.scss';

import './common/cookie';
import './common/form';
import './common/captcha';

import AOS from 'aos';
AOS.init({
    once: true,
});

import './front/home';
