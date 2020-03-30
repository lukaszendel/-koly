
// Utilita pro náhodná čísla
import java.util.Random;

class MineSweeper {
  int Width = 30; // šířka pole
  int Height = 20; // výška pole
  int Mines = 70; // počet min
  char[][] Minefield; // pole pro plochu min

  public MineSweeper() {

    Minefield = new char[Height][Width];

    clearMinefield();
    placeMines();
    drawMinefield();

  }
// funkce generuje miny na náhodná místa
  public void placeMines() {
    int minesPlaced = 0;
    Random random = new Random();
    while(minesPlaced < Mines) {
      int x = random.nextInt(Width);
      int y = random.nextInt(Height);
      // kontroluje, jestli miny nejsou na sobě
      if(Minefield[y][x] != '*') {
        Minefield[y][x] = '*';
        minesPlaced ++;
      }
    }
  }

  public void clearMinefield() {
    // dá na prázdná místa mezeru
    for(int y = 0; y < Height; y ++) {
      for(int x = 0; x < Width; x ++) {
        Minefield[y][x] = ' ';
      }
    }
  }

  public void drawMinefield() {
    for(int y = 0; y < Height; y ++) {
      for(int x = 0; x < Width; x ++) {
        System.out.print(Minefield[y][x]);
      }
      System.out.print("\n");
    }
  }

  public char minesNear(int y, int x) {
    int mines = 0;
    // zkontroluj miny ve všech směrechs
    mines += mineAt(y - 1, x - 1);
    mines += mineAt(y - 1, x);
    mines += mineAt(y - 1, x + 1);
    mines += mineAt(y, x - 1);
    mines += mineAt(y, x + 1);
    mines += mineAt(y + 1, x - 1);
    mines += mineAt(y + 1, x);
    mines += mineAt(y + 1, x + 1);
    if(mines > 0) {
      // změna integeru na char pomocí ascii tabulky
      return (char)(mines + 48);
    } else {
      return ' ';
    }
  }

  // vrátí 1 (true) když mina je, 0 (false) když není
  public int mineAt(int y, int x) {
    // kontrola, jestli nejsme mimo pole
    if(y >= 0 && y < Height && x >= 0 && x < Width && Minefield[y][x] == '*') {
      return 1;
    } else {
      return 0;
    }
  }

  // start aplikace
  public static void main(String[] args) {
    MineSweeper mineSweeper = new MineSweeper();
  }
}