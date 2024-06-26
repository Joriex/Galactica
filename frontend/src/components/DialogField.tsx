

const DialogField = (props:any) => {
    return (
        <div id="dialog-pre-screen" className="fixed bg-g_dark_translucent h-screen w-screen z-50 top-0 left-0 flex justify-center items-center">
            <dialog open className="p-10 bg-white rounded-3xl shadow-2xl w-1/3 flex flex-col justify-between gap-10">
                {props.children}
            </dialog>
        </div>
    )
}

export default DialogField