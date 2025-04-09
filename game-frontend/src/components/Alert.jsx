export default function Alert(props) {
  return (
    <div className="toast-container position-fixed bottom-0 end-0 p-3">
      <div
        className={"toast align-items-center border-0 text-bg-" + props.type}
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
      >
        <div className="d-flex">
          <div className="toast-body">
            {props.message}
          </div>
          <button
            type="button"
            className="btn-close btn-close-white me-2 m-auto"
            data-bs-dismiss="toast"
            aria-label="Close">
          </button>
        </div>
      </div>
    </div>
  )
}