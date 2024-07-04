const thinSelects = document.querySelectorAll('.thin-select');

for (let i = 0; i < thinSelects.length; i++) {
    const thinSelect = thinSelects[i];
    const selectEl = thinSelect.querySelector('select');
    const selectedEl = document.createElement('div');
    const itemsEl = document.createElement('div');

    selectedEl.className = 'thin-select--selected';
    selectedEl.textContent = selectEl.options[selectEl.selectedIndex].textContent;

    itemsEl.className = 'thin-select--items select-hide';

    for (let j = 1; j < selectEl.length; j++) {
        const optionEl = selectEl.options[j];
        const optionWrapperEl = document.createElement('div');
        optionWrapperEl.textContent = optionEl.textContent;
        optionWrapperEl.addEventListener('click', (e) => {
            selectEl.selectedIndex = j;
            selectedEl.textContent = optionEl.textContent;
            const sameAsSelected = itemsEl.querySelector('.same-as-selected');
            if (sameAsSelected) {
                sameAsSelected.classList.remove('same-as-selected');
            }
            optionWrapperEl.classList.add('same-as-selected');
            selectedEl.click();
        });
        itemsEl.appendChild(optionWrapperEl);
    }

    thinSelect.appendChild(selectedEl);
    thinSelect.appendChild(itemsEl);

    selectedEl.addEventListener('click', (e) => {
        e.stopPropagation();
        closeAllSelects(itemsEl);
        itemsEl.classList.toggle('select-hide');
        selectedEl.classList.toggle('select-arrow-active');
    });
}

function closeAllSelects(excludeEl) {
    const itemsEls = document.querySelectorAll('.thin-select--items');
    const selectedEls = document.querySelectorAll('.thin-select--selected');
    for (let i = 0; i < itemsEls.length; i++) {
        const itemsEl = itemsEls[i];
        const selectedEl = selectedEls[i];
        if (itemsEl !== excludeEl) {
            itemsEl.classList.add('select-hide');
            selectedEl.classList.remove('select-arrow-active');
        }
    }
}

document.addEventListener('click', (e) => {
    closeAllSelects();
});
