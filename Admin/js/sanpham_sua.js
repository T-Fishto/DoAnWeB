document.addEventListener('DOMContentLoaded', function() 
{ 
    const updateButton = document.getElementById('update-btn'); 
    const formInputs = document.querySelectorAll('.form-control');
    
    function enableButton() 
    {
        updateButton.disabled = false;
    }

    formInputs.forEach(function(input) 
    {
        if (input.type === 'file') 
        {
            input.addEventListener('change', enableButton);
        } 
        else 
        {
            input.addEventListener('input', enableButton);
        }
    });
});