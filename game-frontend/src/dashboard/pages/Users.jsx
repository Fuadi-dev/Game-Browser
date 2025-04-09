import { useState, useEffect, useContext } from "react"
import SessionContext from '../../components/Session'
import DataTable from 'react-data-table-component'

export default function Users (){
    const session = useContext(SessionContext)
    const [data, setData] = useState([]);
	const [loading, setLoading] = useState(false);
	const [totalRows, setTotalRows] = useState(0);
	const [perPage, setPerPage] = useState(10);
    const columns = [
        {
            name: 'Name',
            selector: row => row.name,
        },
    ];

    const fetchUsers = async page => {
		setLoading(true);
        //get token from session storage
        const token = sessionStorage.getItem('token') || null;

		const response = await fetch(`http://127.0.0.1:8000/api/users?page=${page}&per_page=${perPage}`, {
			method: 'GET',
			headers: {
				'Content-Type': 'application/json',
				'Authorization': `Bearer ${token}`,
			},
		})
        const data = await response.json();
		setData(data.data.data);
		setTotalRows(data.total);
		setLoading(false);
	};
    const handlePageChange = page => {
		fetchUsers(page);
	};

    const handlePerRowsChange = async (newPerPage, page) => {
		setLoading(true);
        //get token from session storage
        const token = sessionStorage.getItem('token') || null;

		const response = await fetch(`http://127.0.0.1:8000/api/users?page=${page}&per_page=${newPerPage}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',

                'Authorization': `Bearer ${token}`,
            },
        });
        const data = await response.json();
        console.log(data);
		setData(data.data.data);
		setPerPage(newPerPage);
		setLoading(false);
	};

    useEffect(() => {
		fetchUsers(1); // fetch page 1 of users
		
	}, []);

    return (
        <DataTable
        title="Users"
        columns={columns}
        data={data}
        progressPending={loading}
        pagination
        paginationServer
        paginationTotalRows={totalRows}
        onChangeRowsPerPage={handlePerRowsChange}
        onChangePage={handlePageChange}
      />
    )
}