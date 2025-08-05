  const content = document.getElementById("zoomableContent");
  let scale = 1;
  let panX = 0;
  let panY = 0;
  let startX, startY;
  let isPanning = false;

  function updateTransform() {
    content.style.transform = `translate(${panX}px, ${panY}px) scale(${scale})`;
  }

  function zoom(direction) {
    const factor = 0.1;
    if (direction === 'in') {
      scale += factor;
    } else if (direction === 'out' && scale > factor) {
      scale -= factor;
    }
    updateTransform();
  }

  content.addEventListener('mousedown', (e) => {
    isPanning = true;
    startX = e.clientX - panX;
    startY = e.clientY - panY;
  });

  document.addEventListener('mousemove', (e) => {
    if (!isPanning) return;
    panX = e.clientX - startX;
    panY = e.clientY - startY;
    updateTransform();
  });

  document.addEventListener('mouseup', () => isPanning = false);
  document.addEventListener('mouseleave', () => isPanning = false);

  updateTransform();
