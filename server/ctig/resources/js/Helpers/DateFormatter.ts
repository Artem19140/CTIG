export class DateFormatter {
  protected raw: string;

  constructor(date: string) {
    this.raw = date;
  }

  format(fmt: string) {
    const pad = (n: number) => String(n).padStart(2, '0');

    const [datePart, timePart] = this.raw.split('T');

    if (!datePart || !timePart) return '';

    const [Y, M, D] = datePart.split('-');
    const [H, i, s] = timePart.replace(/Z|[+-].*$/, '').split(':');

    const map = {
      H: pad(Number(H)),
      i: pad(Number(i)),
      s: pad(Number(s)),
      d: D,
      m: M,
      Y: Y,
    };

    return fmt.replace(/H|i|s|d|m|Y/g, (m) => map[m as keyof typeof map]);
  }
}