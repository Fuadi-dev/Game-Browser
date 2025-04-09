import { useEffect, useState, useContext } from "react"
import SessionContext from '../components/Session'

export default function Play(props) {
  const [game, setGame] = useState(null)
  const session = useContext(SessionContext)

  useEffect(()=> {
    const getGame = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/game/${props.id}`)
        const data = await response.json()
        if(data.status == 'success'){
          setGame(data.game)
        }else{
          setGame(null)
        }

      }catch (error) {
        console.error("Error fetching data:", error);
      }
    }
    if(!session.get.token){
      session.set({page: 'login', message : {type : 'danger', text: 'You need to login to play this game'}})
    }else{
      getGame()
    }
  }, [])
  return game == null ? <>Loading...</> : (
    <div className="w-100 h-100 position-fixed top-0 start-0 bg-dark text-light m-0 p-0">
      {/* <h1>{game.name}</h1> */}
      <iframe className="w-100 h-100" src={game.gameUrl + '/'}></iframe>
      <a href={`#game/${props.id}` } className="text-light position-fixed top-0 start-0 ms-4 mt-4" onClick={()=>session.set({page: 'game', data: props.id})}>back</a>
    </div>
  )
}   