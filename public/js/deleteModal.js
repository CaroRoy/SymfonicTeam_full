const openDeleteModal = document.getElementById("js-openModalDelete");
const deleteModal = document.getElementById("deleteEventModal");
const closeDeleteModal = Array.from(
  document.querySelectorAll("[data-dismiss]")
);
const main = document.querySelector("main");

if (openDeleteModal) {
  openDeleteModal.addEventListener("click", function () {
    deleteModal.style.display = "initial";
    main.style.opacity = "0.5";
  });
}

if (closeDeleteModal.length > 0) {
  closeDeleteModal.forEach((closeButton) => {
    closeButton.addEventListener("click", function () {
      if (deleteModal) {
        deleteModal.style.display = "none";
        main.style.opacity = "1";
      }
    });
  });
}
