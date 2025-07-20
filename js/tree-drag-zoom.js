document.addEventListener("DOMContentLoaded", function () {
  let isDragging = false;
  let startX, startY;
  window.currentX = 0;
  window.currentY = 0;
  window.scale = 1;

  const treeCanvas = document.getElementById("treeCanvas");
  const treeWrapper = document.getElementById("treeWrapper");

  window.updateTransform = function () {
    console.log("update Transform");
    treeWrapper.style.transform = `translate(${window.currentX}px, ${window.currentY}px) scale(${window.scale})`;
  };

  if (treeCanvas && treeWrapper) {
    treeCanvas.addEventListener("mousedown", (e) => {
      isDragging = true;
      startX = e.clientX - window.currentX;
      startY = e.clientY - window.currentY;
      treeCanvas.style.cursor = "grabbing";
    });

    document.addEventListener("mousemove", (e) => {
      if (!isDragging) return;
      window.currentX = e.clientX - startX;
      window.currentY = e.clientY - startY;
      window.updateTransform();
    });

    document.addEventListener("mouseup", () => {
      isDragging = false;
      treeCanvas.style.cursor = "grab";
    });

    treeCanvas.addEventListener("wheel", (e) => {
      e.preventDefault();
      const zoomFactor = 0.1;
      if (e.deltaY < 0) {
        window.scale += zoomFactor;
      } else {
        window.scale = Math.max(0.1, window.scale - zoomFactor);
      }
      window.updateTransform();
    });
  }
});
