

export const formatterDate = (date:string) => {
  if(!date){
      return '-'
  }
  return new Date(date).toLocaleDateString('ru-RU')
}

export const formatterTime = (date:string) :string => {
  if(!date){
    return '-'
  }
  return new Date(date).toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' })
}

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