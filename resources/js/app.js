import './bootstrap';

// Receipt printing functionality
window.addEventListener('print-receipt', event => {
    const saleId = event.detail.saleId;
    const printWindow = window.open(`/sales/${saleId}/receipt`, '_blank');
    
    printWindow.onload = function() {
        printWindow.print();
        setTimeout(() => {
            printWindow.close();
        }, 1000);
    };
});
