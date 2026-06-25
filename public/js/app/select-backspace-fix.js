function shouldBlockBackspace(target) {
    if (!(target instanceof HTMLInputElement)) return false;

    // input interno do Choices.js
    if (
        !target.classList.contains('choices__input') &&
        !target.classList.contains('choices__input--cloned')
    ) {
        return false;
    }

    // só bloqueia se a busca estiver vazia
    return (target.value ?? '').trim() === '';
}

function blockBackspace(e) {
    if (e.key !== 'Backspace') return;
    if (!shouldBlockBackspace(e.target)) return;

    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    return false;
}

// captura o mais cedo possível
document.addEventListener('keydown', blockBackspace, true);
document.addEventListener('keyup', blockBackspace, true);
document.addEventListener('keypress', blockBackspace, true);