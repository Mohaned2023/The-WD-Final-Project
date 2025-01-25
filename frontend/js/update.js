const urlParams = new URLSearchParams(window.location.search);
const userId = urlParams.get('id');
// Update the user
async function updateUser() {
    const name = document.getElementById('editName').value;
    const email = document.getElementById('editEmail').value;
    const password = document.getElementById('editPassword').value;

    if (!name && !email && !password) {
        alert('Name, email or password are required.');
        return;
    }

    const userData = {};
    if (name) userData.name = name;
    if (email) userData.email = email;
    if (password) userData.password = password;

    try {
        const response = await fetch(`http://localhost:8000/api/users/update/${userId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            credentials: 'include',
            body: JSON.stringify(userData),
        });

        if (!response.ok) {
            throw new Error('Failed to update user.');
        }

        alert('User updated successfully!');
        window.location.href = 'admin.html'; // Redirect back to the admin page
    } catch (error) {
        console.error('Error updating user:', error);
        alert('Failed to update user. Please try again.');
    }
}