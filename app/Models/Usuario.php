<?php
/**
 * Modelo Usuario
 * 
 * Representa a los usuarios del sistema (admin, tecnico, encargado_almacen)
 * Maneja autenticación y gestión de usuarios
 * 
 * @package App\Models
 */

namespace App\Models;

class Usuario extends Model
{
    protected string $table = 'usuarios';
    protected string $primaryKey = 'id_usuario';
    
    /**
     * Busca un usuario por su correo electrónico
     * 
     * @param string $correo El correo del usuario
     * @return array|false El usuario o false si no existe
     */
    public function findByEmail(string $correo)
    {
        return $this->findOneBy(['correo' => $correo, 'activo' => 1]);
    }
    
    /**
     * Verifica las credenciales de un usuario
     * 
     * @param string $correo El correo del usuario
     * @param string $password La contraseña en texto plano
     * @return array|false El usuario si las credenciales son válidas, false en caso contrario
     */
    public function authenticate(string $correo, string $password)
    {
        $usuario = $this->findByEmail($correo);
        
        if ($usuario && password_verify($password, $usuario['contraseña_hash'])) {
            // Actualizar última sesión
            $this->update($usuario['id_usuario'], [
                'ultima_sesion' => date('Y-m-d H:i:s')
            ]);
            
            return $usuario;
        }
        
        return false;
    }
    
    /**
     * Crea un nuevo usuario con contraseña hasheada
     * 
     * @param array $data Los datos del usuario
     * @return int El ID del usuario creado
     */
    public function createUser(array $data): int
    {
        // Hashear la contraseña
        if (isset($data['password'])) {
            $data['contraseña_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        
        return $this->create($data);
    }
    
    /**
     * Obtiene todos los usuarios activos
     * 
     * @return array Los usuarios activos
     */
    public function getActiveUsers(): array
    {
        return $this->findBy(['activo' => 1], 'nombre ASC');
    }
    
    /**
     * Obtiene usuarios por rol
     * 
     * @param string $rol El rol a filtrar
     * @return array Los usuarios del rol especificado
     */
    public function getUsersByRole(string $rol): array
    {
        return $this->findBy(['rol' => $rol, 'activo' => 1], 'nombre ASC');
    }
    
    /**
     * Cambia la contraseña de un usuario
     * 
     * @param int $id El ID del usuario
     * @param string $newPassword La nueva contraseña en texto plano
     * @return int El número de filas afectadas
     */
    public function changePassword(int $id, string $newPassword): int
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        return $this->update($id, ['contraseña_hash' => $hashedPassword]);
    }
}
