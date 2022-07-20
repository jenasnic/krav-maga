import { bindCaptcha } from './components/captcha';

[...document.querySelectorAll('.g-recaptcha')].forEach((element) => {
  bindCaptcha(element);
});
