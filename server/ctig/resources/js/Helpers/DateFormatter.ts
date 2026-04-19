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
            'H': pad(this.date.getUTCHours()),
            'i': pad(this.date.getUTCMinutes()),
            's': pad(this.date.getUTCSeconds()),
            'd': pad(this.date.getUTCDate()),
            'm': pad(this.date.getUTCMonth() + 1),
            'Y': this.date.getUTCFullYear(),
        };

        return fmt.replace(/H|i|s|d|m|Y/g, (match) => map[match as keyof typeof map].toString());
    }
}