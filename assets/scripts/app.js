import '../styles/app.scss';

import './common/cookie';
import './common/form';
import './common/captcha';
import './common/components/flash';

import AOS from 'aos';
AOS.init({
    once: true,
});

import './front/menu';
import './front/home';
