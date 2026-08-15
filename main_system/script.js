/**
 * script.js
 * Fetches the employee list from the Microservice (System 2) - via the
 * server-side proxy fetch_api.php - and populates the "Assigned Staff"
 * dropdown on the Create and Update forms.
 *
 * @param {string} selectId       id of the <select> to populate
 * @param {string} hiddenNameId   id of the hidden input to store the employee's name
 * @param {string|null} preselectedId  employee id that should be pre-selected (Update page)
 */
async function populateEmployeeDropdown(selectId, hiddenNameId, preselectedId = null) {
    const selectEl = document.getElementById(selectId);
    const hiddenNameEl = document.getElementById(hiddenNameId);

    try {
        const response = await fetch('fetch_api.php');
        if (!response.ok) throw new Error('Network response was not OK');

        const result = await response.json();

        if (result.status !== 'success') {
            throw new Error(result.message || 'Microservice returned an error');
        }

        selectEl.innerHTML = '<option value="">-- Select Assigned Staff --</option>';

        result.data.forEach(emp => {
            const opt = document.createElement('option');
            opt.value = emp.id;
            opt.textContent = `${emp.full_name} (${emp.position})`;
            opt.dataset.name = emp.full_name;

            if (preselectedId && String(emp.id) === String(preselectedId)) {
                opt.selected = true;
                hiddenNameEl.value = emp.full_name;
            }

            selectEl.appendChild(opt);
        });

        // Keep the hidden "name" field in sync whenever the user changes the dropdown
        selectEl.addEventListener('change', () => {
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            hiddenNameEl.value = selectedOption ? (selectedOption.dataset.name || '') : '';
        });

    } catch (err) {
        selectEl.innerHTML = '<option value="">⚠ Unable to load staff list</option>';
        console.error('Failed to load employees from microservice:', err);
    }
}
