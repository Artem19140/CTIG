import { reactive } from 'vue'

export const confirmDialog = reactive({
  isOpen: false as boolean,
  title: '' as string,
  message: '' as string,
  okText: '' as string,
  cancelText: '' as string,
  confirmText: '' as string,
  needConfirmText: false as boolean,
  errorMessages: '' as string,
  subtitle:'' as string,
  placeholder:'' as string,
  onConfirm: null as null | ((text? :string) => void)
})

export const useConfirmDialog = () => {
    const open = (opitons: {
        title?:string,
        message? : string,
        okText?:string,
        cancelText? :string,
        needConfirmText?:boolean,
        errorMessages?:string,
        subtitle?:string,
        onConfirm?: (text?: string) => void,
        inputPlaceholder?:string
    }) => {
        confirmDialog.title = opitons.title ?? "Подтверждение",
        confirmDialog.message = opitons.message ?? '',
        confirmDialog.okText = opitons.okText ?? 'Подтвердить',
        confirmDialog.cancelText = opitons.cancelText ?? 'Отмена',
        confirmDialog.needConfirmText = opitons.needConfirmText ?? false
        confirmDialog.confirmText = '',
        confirmDialog.onConfirm = opitons.onConfirm ?? null,
        confirmDialog.isOpen = true,
        confirmDialog.errorMessages = opitons.errorMessages ?? ''
        confirmDialog.subtitle = opitons.subtitle ?? ''
        confirmDialog.placeholder = opitons.inputPlaceholder ?? ''
    }
    const close = () => {
        confirmDialog.isOpen=false
        confirmDialog.confirmText = ''
        confirmDialog.title = ''
        confirmDialog.subtitle = ''
        confirmDialog.errorMessages = ''
        confirmDialog.onConfirm = null
        confirmDialog.placeholder = ''
        confirmDialog.message = ''
    }
    return {confirmDialog, open, close}
}
