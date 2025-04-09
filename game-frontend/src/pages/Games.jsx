import { useState, useEffect, useContext } from "react"
import SessionContext from '../components/Session'

export default function Games() {
  const session = useContext(SessionContext)
  const [games, setGames] = useState(null)
  const [categories, setCategories] = useState([])
  const [searchParams, setSearchParams] = useState({
    name: '',
    category: ''
  })
  const [isSearching, setIsSearching] = useState(false)
  
  // Fetch all games initially
  useEffect(() => {
    const getGames = async () => {
      const response = await fetch(`http://127.0.0.1:8000/api/game`)
      const data = await response.json()
      if(data.status === 'success'){
        setGames(data.game)
      } else {
        setGames([])
      }
    }
    
    const getCategories = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/categories`)
        const data = await response.json()
        if(data.status === 'success'){
          setCategories(data.categories)
        }
      } catch (error) {
        console.error('Error fetching categories:', error)
      }
    }
    
    getGames()
    getCategories()
  }, [])
  
  // Handle input changes
  const handleInputChange = (e) => {
    const { name, value } = e.target
    setSearchParams(prev => ({
      ...prev,
      [name]: value
    }))
  }
  
  // Handle search form submission
  const handleSearch = async (e) => {
    e.preventDefault()
    setIsSearching(true)
    
    // Construct query string
    const queryParams = new URLSearchParams()
    if (searchParams.name) queryParams.append('name', searchParams.name)
    if (searchParams.category) queryParams.append('category', searchParams.category)
    
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/game/search?${queryParams.toString()}`)
      const data = await response.json()
      
      if (data.status === 'success') {
        setGames(data.games)
      } else {
        setGames([])
      }
    } catch (error) {
      console.error('Error searching games:', error)
      setGames([])
    } finally {
      setIsSearching(false)
    }
  }
  
  // Reset search
  const handleReset = async () => {
    setSearchParams({ name: '', category: '' })
    
    const response = await fetch(`http://127.0.0.1:8000/api/game`)
    const data = await response.json()
    if(data.status === 'success'){
      setGames(data.game)
    } else {
      setGames([])
    }
  }

  return (
    <>
      <h1>Games</h1>
      
      {/* Search Form */}
      <div className="card bg-dark mb-4">
        <div className="card-body">
          <form onSubmit={handleSearch} className="row g-3">
            <div className="col-md-5">
              <input
                type="text"
                className="form-control"
                placeholder="Search by game name"
                name="name"
                value={searchParams.name}
                onChange={handleInputChange}
              />
            </div>
            
            <div className="col-md-5">
              <select 
                className="form-select"
                name="category"
                value={searchParams.category}
                onChange={handleInputChange}
              >
                <option value="">All Categories</option>
                {categories.map(category => (
                  <option key={category.id} value={category.id}>
                    {category.name}
                  </option>
                ))}
              </select>
            </div>
            
            <div className="col-md-2 d-flex gap-2">
              <button type="submit" className="btn btn-primary" disabled={isSearching}>
                {isSearching ? 'Searching...' : 'Search'}
              </button>
              <button type="button" className="btn btn-secondary" onClick={handleReset}>
                Reset
              </button>
            </div>
          </form>
        </div>
      </div>

      {/* Game List */}
      <div className="row">
        <div className="col">
          {games === null ? (
            <div className="text-center mt-5">
              <div className="spinner-border text-light" role="status">
                <span className="visually-hidden">Loading...</span>
              </div>
              <p className="mt-2">Loading games...</p>
            </div>
          ) : games.length === 0 ? (
            <div className="alert alert-info">No games found matching your criteria.</div>
          ) : (
            <div className="row">
              {games.map((game, i) => (
                <div className="col-2" key={game.id} style={{ margin: '10px' }}>
                  <a
                    href={`#game/${game.id}`}
                    onClick={() => session.set({ page: 'game', data: game.id })}
                  >
                    <figure className="figure">
                      <img
                        src={game.imgUrl}
                        width="100"
                        height="100"
                        className="figure-img rounded-circle bg-light"
                        alt={game.name}
                      />
                      <figcaption className="figure-caption text-light">
                        {game.name}
                      </figcaption>
                    </figure>
                  </a>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </>
  )
}