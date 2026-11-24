export abstract class PasswordHasherPort {
    abstract generarTemporal(passwordTemporal?: string): Promise<{
        passwordTemporal: string;
        passwordHash: string;
    }>;
}
