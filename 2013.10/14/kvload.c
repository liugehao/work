#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <fcntl.h>
#include <sys/mman.h>
#include <sys/stat.h>
typedef enum {false, true} bool;
int main ( int argc, char *argv[] ) {
    // 1 define fp and length
    int fd;
    long length;
    struct stat st;
    // 2 open file
    fd = open ( argv[1], O_RDONLY );

    if ( fd == -1 ) {
        printf ( "failed to open file!\n" );
        exit ( 0 );
    } else {
        // 3 read length
        fstat ( fd, &st );
        length = st.st_size;
        // 4 read content
        char *buf = mmap ( NULL, length, PROT_READ, MAP_SHARED, fd, 0 );
        // 5 calc
        bool infield = false;
        int i, iKey = 0 , iValue = 0, iRow = 0;
        char key[1024];
        char value[8192];
        char c;

        for ( i = 0; i < length; ++i ) {
            c = buf[i];
printf("%c\n", c);  
            // rule delete "
            //      \r \n to space
            switch ( c ) {
            case '"':
                infield = !infield;
                break;

            case ',':
                if ( infield ) {
                    value[iValue++] = ',';
                } else {
                    if ( iRow > 0 ) {
                        value[iValue++] = c;
                    }

                    iRow++;
                }

                break;

            case '\r':
                value[iValue++ ] = ' ';
                break;

            case '\n':
                if ( infield ) {
                    value[iValue++] = ' ';
                } else {
                    key[iKey] = '\0';
                    value[iValue] = '\0';
            
                    if ( iKey > 0 && iValue > 0 ) {
                        //printf ( "*3\r\n$9\r\nds_append\r\n$%d\r\n%s\r\n$%d\r\n%s;\r\n", iKey, key, iValue + 1, value );
                        printf ( "*3\r\n$3\r\nset\r\n$%d\r\n%s\r\n$%d\r\n%s\r\n", iKey, key, iValue, value );
                    }

                    iKey = 0;
                    iValue = 0; 
                    iRow = 0;
                }

                break;

            default:
                if ( iRow == 0 ) {
                    key[iKey++] = c;
                } else {
                    value[iValue++] = c;
                }

                continue;
            }
        }

        // 6 free
        close ( fd );
    }
}

