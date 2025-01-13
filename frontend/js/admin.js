
async function fetchUsers() {
    try {
        
        const response = await fetch('http://localhost:8000/api/users', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'applections/json',
            },
            credentials: 'include'
        });
        
        if (!response.ok) {
            window.location.replace('404.html');
            return;
        }
        
        const users = await response.json();
        
        injectUsersIntoTable(users);
    } catch (error) {
        console.error('Error fetching users:', error);
        alert('Failed to fetch users. Please try again.');
    }
}


function injectUsersIntoTable(users) {
    const tbody = document.getElementById('users-table-body');
    
    tbody.innerHTML = '';
    
    users.forEach(user => {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.created_at}</td>
            <td>${user.updated_at}</td>
            <td>
                <button class="delete-btn" onclick="deleteUser(${user.id}, '${user.name}')">Delete</button>
            </td>
        `;
        
        tbody.appendChild(row);
    });
}


async function deleteUser(id, userName) {
    if (confirm(`Are you sure you want to delete ${userName} with id ${id}?`)) {
        const res = await fetch(`http://localhost:8000/api/users/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'applections/json',
            },
            credentials: 'include'
        });
        if ( res.status != 200 ) {
            console.log(res);
            alert('Failed to delete the users. Please try again.');
            return;
        }
        alert(`'${userName}' with id '${id}' has been deleted.`);
        location.reload();
    }
}


document.addEventListener('DOMContentLoaded', fetchUsers);