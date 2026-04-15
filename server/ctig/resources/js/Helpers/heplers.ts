import { Attempt, Exam } from "@interfaces/Interfaces"

export const downloadFile = (blob: Blob) => {
  if(!blob) return
  const url = window.URL.createObjectURL(blob);
  
  const a = document.createElement("a");
  a.href = url;
  a.download = "file";
  document.body.appendChild(a);
  a.click();
  a.remove();

  window.URL.revokeObjectURL(url);
} 

export const attemptStatus = (status: string | null) => {
  switch (status) {
    case "pending":
      return { text: "Введен код", color: "orange" };
    case "active":
      return { text: "Активна", color: "green" };
    case "finished":
      return { text: "Завершена", color: "grey" };
    case "banned":
      return { text: "Аннулирована", color: "red" };
    case "checked":
      return { text: "Проверена", color: "blue" };
    default:
      return { text: "-", color: "" };
  }
};

export const examStatus = (item: Exam) => {
  if(!item) return { text: "-", color: "grey" };
  if(item.status === 'cancelled') return { text: "отменен", color: "red" };
  if(item.status === 'going') return { text: "В процессе", color: "green" };
  if(item.status === 'completed') return { text: "прошел", color: "grey" };
  return { text: "ожидается", color: "blue" };
};




export const examResultStatus = (
  status:string | null
) => {
  if(!status)  return { text: "Ожидается", color: 'text-grey' };

  if( status === 'pending' ){ //&& !isPast
    return { text: "Ожидается", color: 'text-grey' };
  }

  if (status === 'absent' ) { //&& isPast
    return { text: "н/я", color: "text-gray" }; 
  }

  if (status === 'cancelled' ) { //&& isPast
    return { text: "Отменена", color: "text-gray" }; 
  }

  if (status === 'rescheduled' ) { //&& isPast
    return { text: "Перенесена", color: "text-gray" }; 
  }

  if(status === 'banned'){
    return { text: "Снят", color: "text-red" };
  }

  // if( status === 'pending'){
  //   return { text: "Введен код", color: "text-blue" };
  // }

  if(status === 'finished'){
    return { text: "На проверке", color: "text-orange" };
  }

  if(status === 'going'){
    return { text: "Идет экзамен", color: "text-blue" };
  }

  // return st.isPassed
  //   ? { text: "Пройдено", color: "text-green" }
  //   : { text: "Не пройдено", color: "text-red" };
};

export const capacityColor = (exam : Exam | null) => {
  if(!exam) return 'grey lighten-2'
  return (exam?.capacity && exam?.foreignNationalsCount / exam?.capacity === 1) ? 'green' : 'grey lighten-2'
}