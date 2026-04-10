export class DateFormatter{
    protected date:Date
    constructor(date: string){
        this.date = new Date(date)
    }
    format(fmt:string) {
        if (isNaN(this.date.getTime())) {
            return '';
        }
        const pad = (n:number) => String(n).padStart(2, '0');

        const map = {
            'H': pad(this.date.getHours()),
            'i': pad(this.date.getMinutes()),
            's': pad(this.date.getSeconds()),
            'd': pad(this.date.getDate()),
            'm': pad(this.date.getMonth() + 1),
            'Y': this.date.getFullYear(),
        };

        return fmt.replace(/H|i|s|d|m|Y/g, (match) => map[match as keyof typeof map].toString());
    }
}