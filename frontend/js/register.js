
async function register(event) {
    event.preventDefault();
    
    let name = document.getElementById("full-name").value;
    let email = document.getElementById("TheEmail").value;
    let password = document.getElementById("password").value;
    let agreeTerms = document.getElementById("flexCheckDefault").checked;

    if ( !(name && email && password && agreeTerms) ){
        alert("Some Fildes are massing!!");
        return;
    }

    const res = await fetch('http://localhost:8000/api/users/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'applections/json',
        },
        credentials: 'include',
        body: JSON.stringify({ name, email, password}),
    });
    const data = await res.json();
    if ( data.error || data.message || res.status != 201) { 
        alert( data.error || data.message || "Server Error!!" );
        return;
    }
    alert("Created Successfully.");
}