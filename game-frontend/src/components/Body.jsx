import { useContext } from "react"
import SessionContext from "./Session"
import routes from "../routes"

export default function Body() {
  
    const session = useContext(SessionContext)
    const page = session.get.page;
    const data = session.get.data;

    return routes[page]?.call(null, data) ?? routes.error(404);

  }
  