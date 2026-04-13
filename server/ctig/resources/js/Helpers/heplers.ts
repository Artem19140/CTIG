import { Attempt, Exam } from "../interfaces/interfaces"

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
      return { text: "Введен код", color: "text-orange" };
    case "active":
      return { text: "Активна", color: "text-green" };
    case "finished":
      return { text: "Завершена", color: "text-grey" };
    case "banned":
      return { text: "Аннулирована", color: "text-red" };
    case "checked":
      return { text: "Проверена", color: "text-blue" };
    default:
      return { text: "-", color: "" };
  }
};



export const examStatus = (item: {
  isGoing: boolean;
  isPast: boolean;
  isCancelled: boolean;
}) => {
  if(!item) return { text: "-", color: "text-grey" };
  if (item.isCancelled) return { text: "отменен", color: "text-red" };
  if (item?.isGoing) return { text: "в процессе", color: "text-green" };
  if (item?.isPast) return { text: "прошел", color: "text-grey" };
  return { text: "ожидается", color: "text-blue" };
};




export const attemptResultStatus = (
  attempt:Attempt | null,
  isPast: boolean | undefined
) => {

  if(!attempt && !isPast){
    return { text: "Ожидается", color: 'text-grey' };
  }

  if (!attempt && isPast) {
    return { text: "н/я", color: "text-gray" }; 
  }

  if(attempt?.status === 'banned'){
    return { text: "Снят", color: "text-red" };
  }

  if(attempt?.status === 'pending'){
    return { text: "Введен код", color: "text-blue" };
  }

  if(attempt?.status === 'finished'){
    return { text: "На проверке", color: "text-orange" };
  }

  if(attempt?.isPassed === null){
    return { text: "Идет экзамен", color: "text-blue" };
  }

  return attempt?.isPassed
    ? { text: "Пройдено", color: "text-green" }
    : { text: "Не пройдено", color: "text-red" };
};

export const capacityColor = (exam : Exam | null) => {
  if(!exam) return 'grey lighten-2'
  return (exam?.capacity && exam?.foreignNationalsCount / exam?.capacity === 1) ? 'green' : 'grey lighten-2'
}