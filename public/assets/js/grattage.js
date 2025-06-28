
document.addEventListener("DOMContentLoaded", function () {
  const canvas = document.getElementById("scratchCanvas");
  if (!canvas) return;

  canvas.style.width = "300px";
  canvas.style.height = "150px";
  canvas.width = canvas.offsetWidth;
  canvas.height = canvas.offsetHeight;

  console.log("Taille canvas :", canvas.width, "x", canvas.height);


  const ctx = canvas.getContext("2d");
  let isDrawing = false;

  ctx.fillStyle = "#ccc";
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  ctx.font = "24px Poppins, sans-serif";
  ctx.fillStyle = "#999";
  ctx.textAlign = "center";
  ctx.textBaseline = "middle";
  ctx.fillText("Gratte ici !", canvas.width / 2, canvas.height / 2);

  function checkCompletion() {
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    let cleared = 0;
    for (let i = 0; i < imageData.data.length; i += 4) {
      if (imageData.data[i + 3] < 128) {
        cleared++;
      }
    }
    const percent = (cleared / (canvas.width * canvas.height)) * 100;
    if (percent > 50) {
      document.getElementById("gainMessage").style.display = "flex";
      document.getElementById("cochonDance").style.display = "block";
      canvas.style.pointerEvents = "none";
    }
  }

  canvas.addEventListener("mousedown", () => (isDrawing = true));
  canvas.addEventListener("mouseup", () => (isDrawing = false));
  canvas.addEventListener("mouseleave", () => (isDrawing = false));
  canvas.addEventListener("mousemove", function (e) {
    if (!isDrawing) return;
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    ctx.globalCompositeOperation = "destination-out";
    ctx.beginPath();
    ctx.arc(x, y, 15, 0, Math.PI * 2);
    ctx.fill();
    checkCompletion();
  });
});
