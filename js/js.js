document.addEventListener("DOMContentLoaded", function () {
  let isDragging = false;
  let startX, startY;
  let currentX = 0, currentY = 0;

  const treeCanvas = document.getElementById("treeCanvas");
  const treeWrapper = document.getElementById("treeWrapper");

  if (treeCanvas && treeWrapper) {
    treeCanvas.addEventListener("mousedown", (e) => {
      isDragging = true;
      startX = e.clientX - currentX;
      startY = e.clientY - currentY;
      treeCanvas.style.cursor = "grabbing";
    });

    document.addEventListener("mousemove", (e) => {
      if (!isDragging) return;
      currentX = e.clientX - startX;
      currentY = e.clientY - startY;
      treeWrapper.style.transform = `translate(${currentX}px, ${currentY}px)`;
    });

    document.addEventListener("mouseup", () => {
      isDragging = false;
      treeCanvas.style.cursor = "grab";
    });
  }
});
