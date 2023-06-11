// Function to handle sorting when header cells are clicked
function handleSortClick(event) {
  const th = event.target.closest("th"); // Get the clicked header cell
  const columnIndex = Array.from(th.parentNode.children).indexOf(th); // Get the index of the clicked cell

  const table = document.querySelector(".table"); // Select the table element
  const tbody = table.querySelector("tbody"); // Select the table body
  const rows = Array.from(tbody.querySelectorAll("tr")); // Get an array of all table rows

  // Determine the sorting order (asc or desc)
  let sortOrder = "asc";
  if (th.dataset.sortOrder === "asc") {
    sortOrder = "desc";
  }
  th.dataset.sortOrder = sortOrder;

  // Sort the rows based on the column values
  rows.sort((rowA, rowB) => {
    const cellA = rowA.children[columnIndex].textContent.trim();
    const cellB = rowB.children[columnIndex].textContent.trim();

    // Compare the cell values based on the data type (e.g., ID, date, or time)
    if (th.classList.contains("sort-id")) {
      return sortOrder === "asc" ? cellA - cellB : cellB - cellA;
    } else if (th.classList.contains("sort-date")) {
      const dateA = new Date(cellA);
      const dateB = new Date(cellB);
      return sortOrder === "asc" ? dateA - dateB : dateB - dateA;
    } else if (th.classList.contains("sort-time")) {
      const timeA = new Date(`2000-01-01 ${cellA}`);
      const timeB = new Date(`2000-01-01 ${cellB}`);
      return sortOrder === "asc" ? timeA - timeB : timeB - timeA;
    }

    // For other data types, compare as strings
    return sortOrder === "asc"
      ? cellA.localeCompare(cellB)
      : cellB.localeCompare(cellA);
  });

  // Clear the table body
  tbody.innerHTML = "";

  // Re-append the sorted rows to the table body
  rows.forEach((row) => {
    tbody.appendChild(row);
  });

  // Remove sorting classes and reset text style for all header cells
  th.parentNode.querySelectorAll(".sortable").forEach((cell) => {
    cell.classList.remove("sort-asc", "sort-desc");
    cell.querySelector(".sort-icon").className = "fas fa-sort sort-icon";
    cell.style.color = ""; // Reset text color
    cell.style.fontWeight = ""; // Reset font weight
  });

  // Reset text style for all column contents
  rows.forEach((row) => {
    Array.from(row.children).forEach((cell, index) => {
      if (index !== columnIndex) {
        cell.style.color = ""; // Reset column content text color
        cell.style.fontWeight = ""; // Reset column content font weight
      }
    });
  });

  // Add the appropriate sorting class to the clicked header cell
  th.classList.add(sortOrder === "asc" ? "sort-asc" : "sort-desc");

  // Update the sort icon
  const sortIcon = th.querySelector(".sort-icon");
  sortIcon.className =
    sortOrder === "asc"
      ? "fas fa-sort-up sort-icon"
      : "fas fa-sort-down sort-icon";

  // Set text style for the sorted column
  th.style.color = "#44347c";
  th.style.fontWeight = "bold";
  rows.forEach((row) => {
    row.children[columnIndex].style.color = "#44347c";
    row.children[columnIndex].style.fontWeight = "bold";
  });
}

// Add event listeners to the sortable header cells
document.querySelectorAll(".sortable").forEach((th) => {
  th.dataset.sortOrder = "asc"; // Set initial sort order to ascending
  th.addEventListener("click", handleSortClick);
});

// powered by gpt