const syncCategoryOptions = () => {
    const typeSelect = document.querySelector('[data-transaction-type]');
    const categorySelect = document.querySelector('[data-category-select]');

    if (!typeSelect || !categorySelect) {
        return;
    }

    const selectedType = typeSelect.value;

    Array.from(categorySelect.options).forEach((option) => {
        if (!option.dataset.type) {
            return;
        }

        const matches = option.dataset.type === selectedType;
        option.hidden = !matches;
        option.disabled = !matches;
    });

    const current = categorySelect.selectedOptions[0];

    if (current && current.dataset.type && current.dataset.type !== selectedType) {
        categorySelect.value = '';
    }
};

document.addEventListener('DOMContentLoaded', syncCategoryOptions);
document.addEventListener('change', (event) => {
    if (event.target.matches('[data-transaction-type]')) {
        syncCategoryOptions();
    }
});
