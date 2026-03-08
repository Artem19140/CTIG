export const formatterDate = (date:string) => {
    return new Date(date).toLocaleDateString('ru-RU')
}

export const formatterTime = (date:string) :string => {
    if(!date){
        return 'не установлено'
    }
    return new Date(date).toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' })
}

export const attemptStatus = (attempt : any) => {
  if (attempt.status === 'banned') return 'Аннулирована'
  if (attempt.status === 'finished' && !attempt.isPassed) return 'На проверке'
  return attempt.isPassed ? 'Пройдена' : 'Не пройдена'
}