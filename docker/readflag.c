#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

int main() {
    if (setreuid(0, 0) != 0) {
        printf("Failed to set root privileges\n");
        return 1;
    }
    
    FILE *flag = fopen("/flag.txt", "r");
    if (flag == NULL) {
        printf("Failed to open flag file\n");
        return 1;
    }
    
    char buf[1024];
    fgets(buf, sizeof(buf), flag);
    printf("%s", buf);
    fclose(flag);
    return 0;
} 