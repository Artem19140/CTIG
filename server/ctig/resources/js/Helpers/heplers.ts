export const formatterDate = (date:string) => {
    return new Date(date).toLocaleDateString('ru-RU')
}

export const formatterTime = (date:string) => {
    return new Date(date).toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' })
}