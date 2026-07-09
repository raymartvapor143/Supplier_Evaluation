<script>
    /* ================= CALCULATE EVALUATIONS ================= */
function handleCalculate() {
    const modal = document.getElementById('calculateModal');
    const modalBox = document.getElementById('modalBox');
    const modalContent = document.getElementById('modalContent');
    const closeBtns = modal.querySelectorAll('.close-calculate-modal');

    const openModal = () => {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalBox.classList.remove('scale-95', 'opacity-0');
            modalBox.classList.add('scale-100', 'opacity-100');
        }, 10);
    };

    const closeModal = () => {
        modalBox.classList.remove('scale-100', 'opacity-100');
        modalBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    };

    closeBtns.forEach(btn => btn.onclick = closeModal);

    // ================= NEW FILTERS =================
    const search = document.getElementById('report-search')?.value.toLowerCase() || '';
    const selectedDept = document.getElementById('report-department')?.value || '';
    const selectedSupplier = document.getElementById('report-supplier')?.value || '';
    const selectedYear = document.getElementById('report-period-year')?.value || '';

    const filtered = allEvaluations.filter(evaluation => {

        const matchesSearch =
            (evaluation.po_no ?? '').toLowerCase().includes(search) ||
            (evaluation.supplier_name ?? '').toLowerCase().includes(search) ||
            (evaluation.office_name ?? '').toLowerCase().includes(search);

        const matchesDept = !selectedDept || evaluation.office_name === selectedDept;
        const matchesSupplier = !selectedSupplier || evaluation.supplier_name === selectedSupplier;
        const matchesYear = !selectedYear || evaluation.period_year == selectedYear;

        return matchesSearch && matchesDept && matchesSupplier && matchesYear;
    });

    if (filtered.length === 0) {
        alert("No evaluations to calculate.");
        return;
    }

    let totalScore = 0;
    let count = 0;
    const scoresPerPO = [];

    filtered.forEach(evaluation => {
        if (evaluation.total_score != null) {
            totalScore += parseFloat(evaluation.total_score);
            count++;
            scoresPerPO.push({
                poNumber: evaluation.po_no,
                score: parseFloat(evaluation.total_score)
            });
        }
    });

    if (count === 0) {
        alert("No valid scores found in the filtered results.");
        return;
    }

    const averageScore = (totalScore / count).toFixed(2);

    // ================= MODAL UI =================
    modalContent.innerHTML = `
        <div class="p-4 bg-gray-50 rounded-xl shadow-inner">
            <p class="text-lg"><strong>Total POs:</strong> ${count}</p>
            <p class="text-lg"><strong>Average Score:</strong> ${averageScore}%</p>
        </div>

        <div class="mt-6 max-h-96 overflow-y-auto space-y-3">
            ${scoresPerPO.map(s => `
                <div class="p-3 bg-gray-100 rounded-xl shadow-sm">
                    <div class="flex justify-between mb-1">
                        <span class="font-medium text-gray-700">${s.poNumber}</span>
                        <span class="font-medium text-gray-700">${s.score}%</span>
                    </div>
                    <div class="w-full bg-gray-300 rounded-full h-4">
                        <div class="bg-green-500 h-4 rounded-full transition-all duration-1000" style="width: 0%"></div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;

    openModal();

    // animate bars
    const bars = modalContent.querySelectorAll('.bg-green-500');
    bars.forEach((bar, index) => {
        setTimeout(() => {
            bar.style.width = `${scoresPerPO[index].score}%`;
        }, 100);
    });
}
</script>
