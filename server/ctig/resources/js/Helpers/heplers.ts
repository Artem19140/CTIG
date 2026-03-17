export const formatterDate = (date:string) => {
    return new Date(date).toLocaleDateString('ru-RU')
}

export const formatterTime = (date:string) :string => {
    if(!date){
        return 'не установлено'
    }
    return new Date(date).toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' })
}


export const formatterDateTime = (date: string) => {
  if (!date) return '-'

  const d = new Date(date)

  const options: Intl.DateTimeFormatOptions = {
    day: '2-digit',
    month: 'long',   // полное название месяца
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  }

  // форматируем и убираем запятую между датой и временем
  return new Intl.DateTimeFormat('ru-RU', options).format(d).replace(', ', ' ')
}