const ActionButton = (props: any) => {
    return (
        <button onClick={props.onClick} className={`flex items-center gap-2 justify-center rounded-md border-g_light shadow-xl font-bold bg-g_yellow_gradient w-full p-7 hover:brightness-150 transition-all ease-in-out duration-200 hover:scale-105 active:brightness-75 active:scale-100 my-5 ${props.className}`}>
          <div className="font-headline text-2xl">{props.children}</div>
        </button>
    );
}

export default ActionButton