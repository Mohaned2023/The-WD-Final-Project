async function login(event) {
    event.preventDefault();
    let email = document.getElementById("TheEmail").value,
        password = document.getElementById("password").value;
    const res = await fetch('http://localhost:8000/api/users/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'applections/json',
        },
        credentials: 'include',
        body: JSON.stringify({ email, password}),
    });
    const data = await res.json();
    if ( data.error || data.message || res.status != 200) { 
        alert( data.error || data.message || "Server Error!!" );
        return;
    }
    alert("Loged in Successfully.");
}