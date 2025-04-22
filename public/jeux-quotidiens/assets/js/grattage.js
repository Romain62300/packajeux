document.addEventListener("DOMContentLoaded", function () {
  const canvas = document.getElementById("scratchCanvas");
  const ctx = canvas.getContext("2d");
  let isDrawing = false;

  canvas.width = 300;
  canvas.height = 150;

  // Couche à gratter (gris clair)
  ctx.fillStyle = "#ccc";
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  // Dessin du texte "Gratte ici"
  ctx.font = "24px Poppins";
  ctx.fillStyle = "#999";
  ctx.textAlign = "center";
  ctx.fillText("Gratte ici !", canvas.width / 2, canvas.height / 2);

  canvas.addEventListener("mousedown", () => (isDrawing = true));
  canvas.addEventListener("mouseup", () => (isDrawing = false));
  canvas.addEventListener("mouseleave", () => (isDrawing = false));

  canvas.addEventListener("mousemove", (e) => {
    if (!isDrawing) return;

    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    ctx.globalCompositeOperation = "destination-out";
    ctx.beginPath();
    ctx.arc(x, y, 15, 0, Math.PI * 2);
    ctx.fill();
  });
});
