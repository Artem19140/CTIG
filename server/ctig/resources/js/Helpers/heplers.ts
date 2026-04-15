import { Exam } from "@interfaces/Interfaces"

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
    default:
      return { text: "-", color: "" };
  }
};

export const examStatus = (item: Exam) => {
  if(!item) return { text: "-", color: "grey" };
  if(item.status === 'cancelled') return { text: "Отменен", color: "red" };
  if(item.status === 'going') return { text: "В процессе", color: "green" };
  if(item.status === 'finished') return { text: "Завершен", color: "grey" };
  return { text: "Ожидается", color: "blue" };
};




export const examResultStatus = ( status:string | null ) => {  
  if (status === 'absent' ) {
    return { text: "н/я", color: "text-gray" }; 
  }

  if(status === 'banned'){
    return { text: "Снят", color: "text-orange" };
  }

  if(status === 'failed'){
    return { text: "Не сдан", color: "red" };
  }

  if(status === 'passed'){
    return { text: "Сдан", color: "green" };
  }
  return { text: "", color: '' };
};

export const capacityColor = (exam : Exam | null) => {
  if(!exam) return 'grey lighten-2'
  return (exam?.capacity && exam?.foreignNationalsCount / exam?.capacity === 1) ? 'green' : 'grey lighten-2'
}