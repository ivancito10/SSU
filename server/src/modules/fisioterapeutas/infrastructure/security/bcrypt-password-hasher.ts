import * as bcrypt from 'bcrypt';
import { randomBytes } from 'node:crypto';
import { PasswordHasherPort } from '../../domain/ports/password-hasher.port';
export class BcryptPasswordHasher implements PasswordHasherPort {
    async generarTemporal(passwordTemporal = randomBytes(18).toString('base64url')) {
        const passwordHash = await bcrypt.hash(passwordTemporal, 10);
        return {
            passwordTemporal,
            passwordHash
        };
    }
}