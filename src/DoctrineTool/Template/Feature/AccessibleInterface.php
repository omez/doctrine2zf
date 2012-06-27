<?php
namespace DoctrineTool\Template\Feature;

/**
 * Interface for virtual R/W hidden/protected properties.
 * Currently mix of getter/setter interfaces
 *
 * @author Alexander Sergeychik
 * @package DoctrineTool\Template
 * @version 0.2
 */
interface AccessibleInterface extends GetterInvokableInterface, SetterInvokableInterface {}