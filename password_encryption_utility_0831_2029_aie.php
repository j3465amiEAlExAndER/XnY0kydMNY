<?php
// 代码生成时间: 2025-08-31 20:29:20
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class PasswordEncryptionUtility {

    /**
     * Encrypts a plain text password
     *
     * @param string $plainPassword The plain text password to encrypt
     *
     * @return string The encrypted password
     *
     * @throws InvalidArgumentException If the provided password is not a string
     */
    public function encryptPassword(string $plainPassword): string {
        if (!is_string($plainPassword)) {
            throw new InvalidArgumentException('Password must be a string.');
        }

        $encoder = new BCryptPasswordEncoder();
        return $encoder->encodePassword($plainPassword, '');
    }

    /**
     * Decrypts an encrypted password
     *
     * @param string $encryptedPassword The encrypted password to decrypt
     * @param string $plainPassword The plain text password to verify against
     *
     * @return bool True if the passwords match, false otherwise
     *
     * @throws InvalidArgumentException If the provided passwords are not strings
     */
    public function decryptPassword(string $encryptedPassword, string $plainPassword): bool {
        if (!is_string($encryptedPassword) || !is_string($plainPassword)) {
            throw new InvalidArgumentException('Both encrypted and plain passwords must be strings.');
        }

        $encoder = new BCryptPasswordEncoder();
        return $encoder->isPasswordValid($encryptedPassword, $plainPassword);
    }
}
