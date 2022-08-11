import IMask from 'imask';

export const bindInputMask = (input) => {
    IMask(input, {
        mask: input.dataset.maskInput || '',
    });
};

export const bindNumberInput = (input) => {
    IMask(input, {
        mask: Number,
        padFractionalZeros: '0' !== input.dataset.numberScale,
        min: input.dataset.numberMin,
        max: input.dataset.numberMax,
        radix: input.dataset.numberRadix,
        scale: input.dataset.numberScale,
        thousandsSeparator: input.dataset.numberSeparator,
    });
};
