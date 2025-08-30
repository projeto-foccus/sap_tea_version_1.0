document.querySelector(".pdf-button").addEventListener("click", function () {
    const { jsPDF } = window.jspdf;

    // Seleciona a parte da página que será capturada
    const element = document.body;

    // Usa html2canvas para converter a página em imagem
    html2canvas(element, { scale: 1.0 }).then(canvas => { // Reduzindo a escala para diminuir o tamanho
        const imgData = canvas.toDataURL("image/jpeg", 0.8); // Compressão JPEG (0.6 de qualidade)

        const pdf = new jsPDF("p", "mm", "a4"); // Cria um documento PDF

        // Ajusta a imagem no PDF
        const imgWidth = 210; // Largura A4 em mm
        const imgHeight = (canvas.height * imgWidth) / canvas.width; // Mantém proporção

        pdf.addImage(imgData, "JPEG", 0, 0, imgWidth, imgHeight);
        pdf.save("Perfil_Estudante.pdf"); // Baixa o PDF
    });
});