$(document).ready(function() {
        // Edit icon click event
    $('.edit-icon').click(function() {
        var row = $(this).closest('tr');
        var editIcons = row.find('.edit-icon');
        var cells = row.find('td:not(.edit-delete-icons)');
        var recordData = {};
        var id = $(this).data('id');
        var tableName = $(this).data('table');

        cells.each(function() {
            var cell = $(this);
            var columnName = cell.attr('data-column');
            var value = cell.text();

            if (columnName) {
                if (cell.find('input[type="checkbox"]').length > 0) {
                    // For checkboxes, set the checkbox state based on the value retrieved from the database
                    var isChecked = value === 't'; // Assuming the database value is 't' or 'f'
                    var checkboxHTML = '<input type="checkbox" class="form-check-input" ' + (isChecked ? 'checked' : '') + '>';
                    cell.html(checkboxHTML);
                } else {
                    // For other fields, populate with text input values
                    cell.html('<input type="text" class="form-control" value="' + value + '">');
                }
                recordData[columnName] = value; // Store original values
            }
        });

        row.data('recordData', recordData);

        editIcons.hide();
        row.append('<td><button class="btn btn-success save-btn">Save</button></td>');
        row.append('<td><button class="btn btn-secondary cancel-btn">Cancel</button></td>');

        $('.save-btn').click(function() {
            var row = $(this).closest('tr');
            var editIcons = row.find('.edit-icon');
            var cells = row.find('td:not(.edit-delete-icons)');
            var newData = {};
            var recordData = row.data('recordData');

            cells.each(function() {
                var cell = $(this);
                var newValue;
                var columnName = cell.attr('data-column');

                if (columnName) {
                    if (cell.find('input[type="checkbox"]').length > 0) {
                        // For checkboxes, set newValue based on checked state
                        newValue = cell.find('input[type="checkbox"]').prop('checked') ? 't' : 'f';
                        // If not toggled, retain the original boolean value
                        if (!cell.find('input[type="checkbox"]').is(':checked')) {
                            newValue = recordData[columnName];
                        }
                    } else {
                        // For other fields, consider text input values
                        newValue = cell.find('input').val();
                    }
                    newData[columnName] = newValue !== '' ? newValue : null; // Handle empty values as null
                }
            });
            // Ajax request and success/error handling remain unchanged
            $.ajax({
                type: 'POST',
                url: 'edit_icon.php',
                data: { id: id, table: tableName, newData: JSON.stringify(newData) },
                success: function(response) {
                    if (response.trim() === 'success') {
                        editIcons.show();
                        row.find('.save-btn').closest('td').remove();
                        alert('Record updated successfully!');
                    } else {
                        alert('Error updating record: ' + response);
                    }
                },
                error: function(error) {
                    console.error('Error updating record:', error);
                }
            });
        });
        $('.cancel-btn').click(function() {
            var row = $(this).closest('tr');
            var recordData = row.data('recordData');

            // Restore original values
            row.find('td:not(.edit-delete-icons)').each(function() {
                var columnName = $(this).attr('data-column');
                if (columnName) {
                    $(this).text(recordData[columnName]);
                }
            });

            row.find('.edit-icon').show();
            row.find('.save-btn').closest('td').remove();
            row.find('.cancel-btn').closest('td').remove();
        });
    });

    // Delete icon click event
    $('.delete-icon').click(function() {
        var row = $(this).closest('tr');
        var id = $(this).data('id');
        var tableName = $(this).data('table');

        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                type: 'POST',
                url: 'del_icon.php',
                data: { id: id, table: tableName },
                success: function(response) {
                    if (response.trim() === 'success') {
                        row.remove();
                        alert('Record deleted successfully!');
                    } else {
                        alert('Error deleting record: ' + response);
                    }
                },
                error: function(error) {
                    console.error('Error deleting record:', error);
                }
            });
        }
    });
});
